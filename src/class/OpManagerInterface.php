<?php

namespace KV;

interface OpManagerInterface
{
    public function inc();

    public function reset();

    public function isLimit();
}
