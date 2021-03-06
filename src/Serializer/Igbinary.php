<?php

/**
 *
 * This file is part of the Apix Project.
 *
 * (c) Franck Cassedanne <franck at ouarz.net>
 *
 * @license     http://opensource.org/licenses/BSD-3-Clause  New BSD License
 *
 */

namespace Apix\Cache\Serializer;

/**
 * IgBinary - fast binary serializer.
 * Serializes cache data using the IgBinary extension.
 * @see https://github.com/IgBinary/IgBinary
 *
 * @author Franck Cassedanne <franck at ouarz.net>
 */
class Igbinary implements Adapter
{

    /**
     * {@inheritdoc}
     */
    public function serialize($data)
    {
        return \igbinary_serialize($data);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        return \igbinary_unserialize($str);
    }

    /**
     * {@inheritdoc}
     */
    public function isSerialized($str)
    {
        if (!is_string($str)) {
            return false;
        }

        return @substr_count($str, "\000", 0, 3) == 3;
    }

}
