<?php

/*
 * Exp:resso Store module for ExpressionEngine
 * Copyright (c) 2010-2014 Exp:resso (support@exp-resso.com)
 */

namespace Store\Illuminate;

/**
 * Fake PDO for servers with PDO disabled
 */
interface FakePDO
{
    const FETCH_ASSOC = 2;
}
