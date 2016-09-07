<?php

declare (strict_types = 1);

namespace Dumplie\UserInterface\Symfony\MetadataBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class UpdateMetadataSchemaCommand extends Command implements ContainerAwareInterface
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
            ->setName('dumplie:metadata:schema:alter')
            ->setDescription('Alters metadata schema in storage.')
            ->setDefinition(array(
                new InputOption(
                    'force', 'f', InputOption::VALUE_NONE,
                    'Causes the generated change set statements to be physically executed against your storage.'
                ),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = true === $input->getOption('force');

        $builderServiceId = $this->container->getParameter('dumplie.metadata.schema.builder.service');
        $storage = $this->container->get('dumplie.metadata.storage');
        $schema = $this->container->get($builderServiceId)->build();

        $changeSet = $storage->diff($schema);

        $pluralization = (1 === count($changeSet->operations())) ? 'operations was' : 'operations were';

        if ($force) {
            $storage->alter($schema);
            $output->writeln(sprintf('Storage schema updated successfully! "<info>%s</info>" %s made', count($changeSet->operations()), $pluralization));
            return 0;
        }

        $pluralization = (1 === count($changeSet->operations())) ? 'operation' : 'operations';

        $output->writeln(sprintf('This command would execute <info>"%s"</info> %s to update the storage.', count($changeSet->operations()), $pluralization));

        if (count($changeSet->operations())) {
            $output->writeln("");
            $output->writeln("<info>OPERATIONS:</info>");
            $output->writeln(implode(';' . PHP_EOL, $changeSet->operations()) . ';');
            $output->writeln("");

            $output->writeln('Please run the operation by passing following options:');
            $output->writeln(sprintf('    <info>%s --force</info> to execute the command', $this->getName()));
        }
    }
}