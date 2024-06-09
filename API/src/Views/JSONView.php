<?php

namespace Fhtechnikum\Theshop\Views;

class JSONView
{
    /**
     * @param mixed $data
     */
    public function output($data): void
    {
        header("Content-type: application/json");
        echo json_encode($data);
    }
}