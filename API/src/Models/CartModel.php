<?php

namespace Fhtechnikum\Theshop\Models;


use SessionHandlerInterface;

class CartModel
{
public array $items = [];
public SessionHandlerInterface $session;
}