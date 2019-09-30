<?php

namespace App\Commands;

use App\MobileNumberValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Exception\RuntimeException;

abstract class ValidatorCommand extends Command
{
    /**
     * @var mixed
     */
    protected $source;

    /**
     * Initiate a validator for a number.
     *
     * @param  mixed $number
     * @return App\MobileNumberValidator
     */
    abstract public function validatorNumber($number): MobileNumberValidator;

    /**
     * Check if the number is valid?
     *
     * @param  App\MobileNumberValidator $validator
     * @return bool
     */
    abstract public function isNumberValid(MobileNumberValidator $validator): bool;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        //generate 6 random numbers
        $rand = mt_rand(100000, 999999);

        $this->fileName = 'validated_numbers_' . $rand . '.csv';
        $this->outputPath = base_path('output');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->setSource();
        $this->validateOutputPath();
        $this->buildCsv($this->compileRowsFromSource($this->source));
        $this->info('Csv Filename: ' . $this->fileName);
        $this->info('Total Number Validated: ' . count($this->source));
        $this->info('Output Path: ' .$this->outputPath);
    }

    /**
     * Build a CSV file from the listed rows.
     *
     * @param  Collection $rows
     * @return void
     */
    protected function buildCsv(Collection $rows)
    {
        File::put(
            $this->outputPath . '/' . $this->fileName,
            implode("\n", $rows->toArray())
        );
    }

    /**
     * Process a list of numbers.
     *
     * @param array $source
     * @return Illuminate\Support\Collection
     */
    protected function compileRowsFromSource(array $source): Collection
    {
        return collect($source)
            ->map([$this, 'validatorNumber'])
            ->map([$this, 'creatRowFromValidator'])
            ->prepend(['Mobile Number', 'Carrier Name', 'Validity Status'])
            ->map(function ($row) {
                return implode(',', $row);
            });
    }

    /**
     * create a row.
     *
     * @param  App\MobileNumberValidator $validator
     * @return array
     */
    public function creatRowFromValidator(MobileNumberValidator $validator): array
    {
        $row = [$validator->getNumber(), '', 'Invalid'];

        if ($this->isNumberValid($validator)) {
            $row[1] = $validator->getCarrierName();
            $row[2] = 'Valid';
        }

        return $row;
    }

    /**
     * Set the source type.
     */
    protected function setSource(): void
    {
        $this->source = $this->option('file')
            ? $this->getSourceFromFile($this->argument('source'))
            : array_wrap($this->argument('source'));
        $this->info('Source Type: ' . ($this->option('file') ? 'File' : 'List'));
    }

    /**
     * Return the contents of the source file.
     *
     * @return array
     */
    protected function getSourceFromFile($file): array
    {
        $file = head(array_wrap($file));

        throw_unless(
            File::exists($file),
            RuntimeException::class,
            "Source does not exist [{$file}]"
        );

        return array_wrap(explode("\n", File::get($file)));
    }

    /**
     * Set the output path.
     */
    protected function validateOutputPath(): void
    {
        if ($this->option('output')) {
            $this->outputPath = $this->option('output');
        }

        throw_unless(
            is_dir($this->outputPath),
            RuntimeException::class,
            "The output path does not exist [{$this->outputPath}]"
        );
    }
}
