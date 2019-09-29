<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use App\MobileNumberValidator;

class CheckMobileNumberCommand extends ValidatorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:number
                            {source* : A list of numbers or files to validate against}
                            {--file : the source is a list of numbers in file}
                            {--output= : the output path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate mobile number(s) and ouput result of validation';

    /**
     * Declared Country Codes including Channels Island
     *  
     * @var array
     */
    protected $CountryCodes = [
        'GB', 'JE', 'GG', 'IM',
    ];

    /**
     * Initiate a validator for a number.
     *
     * @param  mixed $number
     * @return App\MobileNumberValidator
     */
    public function validatorNumber($number): MobileNumberValidator
    {
        return app(MobileNumberValidator::class)->make($number, 'GB');
    }

    /**
     *  Check mobile number validity?
     *
     * @param  App\MobileNumberValidator $validator
     * @return bool
     */
    public function isNumberValid(MobileNumberValidator $validator): bool
    {
        return $validator->isValidMobile() && $validator->isValidForCountry($this->CountryCodes);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
