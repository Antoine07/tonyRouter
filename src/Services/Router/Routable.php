<?php

namespace Services\Router;

/**
 *
 * @author antoine
 */
interface Routable
{
    function getController();
    function getAction();
    function getParams();
    function isMatch($url);
}
