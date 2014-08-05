<?php

namespace DataTable\DataSource\ServerSide;

use DataTable\Request;
use DataTable\Response;

/**
 * ServerSide Interface
 *
 * @package DataTable\DataSource\ServerSide
 */
interface ServerSideInterface
{
    /**
     * Get DataTable response
     *
     * @param Response $response DataTable response object
     * @param Request  $request  DataTable request object
     *
     * @return void
     */
    public function getResponse(Response $response, Request $request);
}
