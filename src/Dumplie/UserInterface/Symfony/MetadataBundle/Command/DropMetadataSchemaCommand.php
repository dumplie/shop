<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\MetadataBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class DropMetadataSchemaCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function configure()
    {
        $this
            ->setName('dumplie:metadata:schema:drop')
            ->setDescription('Drops metadata schema in storage.')
            ->setDefinition([
                new InputOption(
                    'force', 'f', InputOption::VALUE_NONE,
                    'Causes the generated change set statements to be physically executed against your storage.'
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = true === $input->getOption('force');

        $builderServiceId = $this->container->getParameter('dumplie.metadata.schema.builder.service');
        $storage = $this->container->get('dumplie.metadata.storage');
        $schema = $this->container->get($builderServiceId)->build();

        if ($force) {
            $storage->drop($schema);

            $output->writeln('Storage schema dropped successfully!');
            return 0;
        }

        $output->writeln('Please run the operation by passing following option:');
        $output->writeln(sprintf('    <info>%s --force</info> to execute the command and drop schema in storage', $this->getName()));
        $output->writeln("");
        $output->writeln("<error>WARNING: Data removed during this operation can't be restored automatically.</error>");
    }
}