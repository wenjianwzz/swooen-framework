<?php
namespace Swooen\Console;

use Swooen\Handle\Writer\StdoutWriter;
use Symfony\Component\Console\Output\Output;

class ConsoleWriter extends StdoutWriter {

    protected $output;

    public function __construct(Output $output) {
        $this->output = $output;
    }

	public function write(string $content): bool {
        $this->output->writeln($content);
		return true;
    }

}
