<?php declare(strict_types=1);

namespace Sassnowski\Arcanist\Repository;

use Sassnowski\Arcanist\AbstractAssistant;
use Sassnowski\Arcanist\Contracts\AssistantRepository;
use Sassnowski\Arcanist\Exception\AssistantNotFoundException;

class FakeAssistantRepository implements AssistantRepository
{
    private int $nextId = 1;

    public function __construct(private array $data = [], private array $registeredAssistants = [])
    {
    }

    public function saveData(AbstractAssistant $assistant, array $data): void
    {
        if ($assistant->getId() === null) {
            $assistant->setId($this->nextId++);
        }

        $assistantClass = get_class($assistant);

        $existingData = $this->data[$assistantClass][$assistant->getId()] ?? [];

        $this->data[$assistantClass][$assistant->getId()] = array_merge($existingData, $data);
    }

    public function loadData(AbstractAssistant $assistant): array
    {
        $assistantClass = get_class($assistant);

        if (!isset($this->data[$assistantClass][$assistant->getId()])) {
            throw new AssistantNotFoundException();
        }

        return $this->data[$assistantClass][$assistant->getId()];
    }

    public function deleteAssistant(AbstractAssistant $assistant): void
    {
        unset($this->data[get_class($assistant)][$assistant->getId()]);
        $assistant->setId(null);
    }

    public function registeredAssistants(): array
    {
        return $this->registeredAssistants();
    }
}