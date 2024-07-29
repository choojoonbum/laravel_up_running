<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// php artisan make:mail AssignmentCreated
class AssignmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $trainer;
    public $trainee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trainer, $trainee)
    {
        $this->trainer = $trainer;
        $this->trainee = $trainee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->trainer->name . '으로 부터 새 운동이 할당되었습니다.')
            // ->text('emails.reminder_plan') // Html이 아닌 일반 텍스트 형식
            ->view('emails.assignment-created')
            /*->from()
            //파일 첨부
            ->attach()
            //원시 문자열을 파일로 만들어서 첨부
            ->attachData()
            //파일 시스템 디스크에 저장된 파일 첨부
            ->attachFromStorage()
            // 이메일 우선 순위 설정
            ->priority()*/
            ->with(['assignment' => $this->event->name]);

        // 기본 SwiftMessage 객체 변경하기
        return $this->subject('반갑습니다.')
            ->withSwiftMessage(function ($swift) {
                $swift->setReplyTo('noreply@email.com');
            })
            ->view('emails.welcome');

        // 메일러블에 파일이나 데이터 첨부하기
        // 로컬 파일명을 이용해서 파일 첨부
        return $this->subject('백서 다운로드')
            ->attach(storage_path('pdfs/whitepaper.pdf'),[
                'mime' => 'application/pdf', // 선택사항
                'as' => 'whitepaper-barasa.pdf', // 선택사항
            ])
            ->view('emails.whitepaper');

        // 원시 데이터를 전달해서 파일 첨부
        return $this->subject('백서 다운로드')
            ->attachData(
                file_get_contents(storage_path('pdfs/whitepaper.pdf')),
                'whitepaper-barasa.pdf',
                [
                    'mine' => 'application/pdf', // 선택사항
                ]
            )
            ->view('emails.whitepaper');

        // s3 같은 파일 시스템 디스크에 저장된 파일 첨부
        return $this->subject('백서 다운로드')
            ->view('emials.whitepaper')
            ->attachFromStorage('/pdfs/whitepaper.pdf');
    }
}
