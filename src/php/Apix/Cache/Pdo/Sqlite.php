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

namespace Apix\Cache\Pdo;

use Apix\Cache\Pdo;

/**
 * The SQLite (PDO) wrapper.
 *
 * @author Franck Cassedanne <franck at ouarz.net>
 */
class Sqlite extends Pdo
{

    /**
     * Holds the SQL definitions for SQLite v2/v3.
     */
    public $sql_definitions = array(
        'init'      => 'CREATE TABLE IF NOT EXISTS %s (key VARCHAR PRIMARY KEY,
                        data LONGTEXT, tags TEXT, expire INT, created INT);',
        'key_idx'   => 'CREATE INDEX IF NOT EXISTS %s_key_idx ON %s (key);',
        'exp_idx'   => 'CREATE INDEX IF NOT EXISTS %s_exp_idx ON %s (expire);',
        'tag_idx'   => 'CREATE INDEX IF NOT EXISTS %s_tag_idx ON %s (tags);',
        'loadKey'   => 'SELECT data FROM %s WHERE key=:key AND
                        (expire IS NULL OR expire > :now);',
        'loadTag'   => 'SELECT key FROM %s WHERE tags LIKE :tag AND
                        (expire IS NULL OR expire > :now);',
        'update'    => 'UPDATE %s SET data=:data, tags=:tags, expire=:exp
                        WHERE key=:key;',
        'insert'    => 'INSERT INTO %s (key, data, tags, expire)
                        VALUES (:key, :data, :tags, :exp);',
        'delete'    => 'DELETE FROM %s WHERE key = ?;',
        'clean'     => 'DELETE FROM %s WHERE %s;',
        'flush_all' => 'DROP TABLE IF EXISTS %s;',
        'flush'     => 'DELETE FROM %s;',
        'purge'     => 'DELETE FROM %s WHERE expire IS NOT NULL AND expire < %d;'
    );

    /**
     * Constructor.
     *
     * @param \PDO  $pdo
     * @param array $options Array of options.
     */
    public function __construct(\PDO $pdo, array $options=null)
    {
        // Set some SQLite PRAGMA to speed things up.
        // @see http://www.sqlite.org/pragma.html
        // @see http://stackoverflow.com/questions/1711631/how-do-i-improve-the-performance-of-sqlite
        $pdo->exec('PRAGMA synchronous=OFF');
        $pdo->exec('PRAGMA journal_mode=MEMORY');
        $pdo->exec('PRAGMA temp_store=MEMORY');
        $pdo->exec('PRAGMA count_changes=false');

        parent::__construct($pdo, $options);
    }

}
