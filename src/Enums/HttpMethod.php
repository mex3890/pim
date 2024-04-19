<?php

namespace Pim\Enums;

enum HttpMethod: string
{
    case DELETE = 'delete';
    case GET = 'get';
    case POST = 'post';
    case PATCH = 'patch';
    case CONNECT = 'connect';
    case OPTIONS = 'options';
    case PUT = 'put';
    case HEAD = 'head';
}
