<?php
declare(strict_types=1);

class DefaultController
{
    public function index(): never
    {
        redirect('Product');
    }
}

