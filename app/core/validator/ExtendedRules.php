<?php

declare(strict_types=1);

namespace app\core\validator;

use think\Validate;

class ExtendedRules implements \SplSubject
{
    private \SplObjectStorage $ruleStorage;

    public function __construct()
    {
        $this->ruleStorage = new \SplObjectStorage();
    }

    public function attach(\SplObserver $rule): void
    {
        $weakReference = \WeakReference::create($rule);
        $this->ruleStorage->attach($weakReference->get());
    }

    public function detach(\SplObserver $rule): void
    {
        $this->ruleStorage->detach($rule);
    }

    public function notify(): void
    {
        $this->boot();
    }

    public function boot()
    {
        Validate::maker(function ($validate) {
            foreach ($this->ruleStorage as $rule) {
                $rule->update($validate);
            }
        });
    }
}
