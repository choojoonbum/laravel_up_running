<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class WelcomeNewUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    // php artisan make:command WelcomeNewUsers --command=email:newusers
    // 인자와 옵션을 지정하는 방법
    protected $signature = 'email:newusers {userId} {--sendEmail=}'; //{userId}필수,{--sendEmail} 옵션


    /*
     * password:reset {userId} 필수인자 정의
     * password:reset {userId?} 옵션인자 정의
     * password:reset {userId=1} 인자의 기본값 지정
     * password:reset {userId} {--sendEmail}
     * password:reset {userId} {--password=} 옵션에 값을 필수적으로 지정하는 경우
     * password:reset {userId} {--queue=default} 옵션에 기본값 정의
     * password:reset {userIds*} 배열 형태로 입력받는 경우
     * password:reset {--ids=*} 배열 형태로 입력받는 경우
     *
     * // 아티즌 명령어에 배열 인자를 사용하는 방법
     * // 인자 php artisan password:reset 1 2 3
     * // 옵션 php artisan password:reset --ids=1 --ids=2 --ids=3
     *
     * // 아티즌 명령어에 인자와 옵션에 도움말을 위한 설명을 추가 예제
     * password:reset {userId : The ID of the user} {--sendEmail : Whether to send user an email}
     *
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(/*UserMailer $userMailer*/)
    {
        parent::__construct();
        //$this->userMailer = $userMailer;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
/*        User::signedUpThisWeek()->each(function ($user) {
            \Mail::to($user)->send(new WelcomeEmail);
        });*/
        //$this->userMailer->welcomeNewUsers();

        // arguments() 사용자의 입력값 즉 인자값과 옵션값을 전달 받기 위해 사용
        /* php artisan password:reset 5 콘솔입력시
        arguments() 리턴값 ["command" : "password:reset", "userId" : 5]
        argument('userId') 리턴값 5

        $this->options() 리턴값
            [
              "sendEmail" => "123@ham.com"
              "help" => false
              "quiet" => false
              "verbose" => false
              "version" => false
              "ansi" => null
              "no-interaction" => false
              "env" => null
            ]
        */

        // 명령어의 이름을 포함한 모든 인자를 배열로 조회
        $this->arguments();
        // userId 인자의 값만 조회한다
        $this->argument('userId');
        // 기본값을 포함한 모든 인자를 배열로 조회한다.
        $this->options();
        // sendEmail 옵션 값만 조회한다.
        $this->option('sendEmail');

        // 프롬프트
        $this->ask('이메일 주소는 무엇인가요?');
        $this->secret('데이터베이스 암호는 무엇인가요?');
        if($this->confirm('테이블을 모두 삭제하시겠습니까?')){};
        $this->anticipate('최고의 앨범은 무엇이라고 생각하나요?', ["The Joshua Tree", "Pet Sounds", "Whats Going On"]);
        $this->choice('최고의 축구 팀은 어느 팀인가요?', ['맨체스터 유나이트', '바로셀로나', '레알 마드리드'], 0); //배열 키값 형태도 가능

        // 화면출력
        $this->info("Your command has run successfully.");
        $this->comment("Your command has run successfully.");
        $this->question("Your command has run successfully.");
        $this->error("Your command has run successfully.");
        $this->line("Your command has run successfully.");

        // 테이블 형태로 출력
        $header = ['이름','이메일'];
        $data = [['Dhirit','shrri@hare.com'],['Moses','mosee@agewu.com']];
        $this->table($header, $data);

        // 프로그래스바 출력
        $totalUnits = 20;
        $this->output->progressStart($totalUnits);
        for ($i = 0; $i < $totalUnits; $i++) {
            sleep(1);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        // 특정 아티즌 명령어 클래스에서 다른 아티즌 명령어를 호출하는 방법
        $this->callSilent('password:reset',['userId' => $this->argument('userId')]);

    }

}
