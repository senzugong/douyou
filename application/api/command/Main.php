<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/19
 * Time: 14:16
 */

namespace app\api\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Main extends Command{
    protected function configure(){
        $this->setName('Main')->setDescription("定时产生RMZ");
    }

    protected function execute(Input $input, Output $output){
        $output->writeln('Date Crontab job start...');
        /*** 这里写计划任务列表集 START ***/

        $this->test();

        /*** 这里写计划任务列表集 END ***/
        $output->writeln('Date Crontab job end...');
    }

    private function test(){
        echo "Main\r\n";
    }
}