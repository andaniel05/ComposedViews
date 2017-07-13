<?php

namespace PlatformPHP\ComposedViews\Component;

trait ComponentContainerTrait
{
    protected $components = [];

    public function getAllComponents(): array
    {
        return $this->components;
    }

    private function findOne(array $components, string $id): ?AbstractComponent
    {
        foreach ($components as $component) {
            if ($id == $component->getId()) {
                return $component;
            } elseif ($component instanceOf AbstractComponent) {
                $component = $this->findOne($component->getAllComponents(), $id);
                if ($component) {
                    return $component;
                }
            }
        }

        return null;
    }

    public function getComponent(string $id): ?AbstractComponent
    {
        $idList = preg_split('/\s+/', $id);

        if (1 == count($idList)) {
            return $this->findOne($this->getAllComponents(), $id);
        }

        $hash = array_fill_keys($idList, null);

        $container = $this;
        for ($i = 0; $i < count($idList); $i++) {

            $componentId = $idList[$i];
            $component = $container->getComponent($componentId);

            if ($component) {
                $hash[$componentId] = $component;
                $container = $component;
            } else {
                break;
            }
        }

        return array_pop($hash);
    }

    public function addComponent(AbstractComponent $component)
    {
        $this->components[$component->getId()] = $component;

        if ($this instanceOf ComponentContainerInterface) {
            $component->setParent($this);
        }
    }

    public function dropComponent(string $id, bool $notifyChild = true)
    {
        $component = $this->components[$id] ?? null;
        if ($component) {

            if ($notifyChild) {
                $component->setParent(null);
            }

            unset($this->components[$id]);
        }
    }

    public function existsComponent(string $id): bool
    {
        return isset($this->components[$id]);
    }
}