<?php

// <PROGRAM_NAME>
// Copyright (C) 2010 Magnus Söderling (magnus.soderling@gmail.com)
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A class that connetcts to an ldap-server and facilitates running searches.
 */
class LDAP {

    protected $hostUrl;
    protected $hostPort;
    protected $maxEntriesToGet;
    //Max entries to get from LDAP-server.
    protected $baseDN;
    protected $ldapAttributes;
    protected $ldapConnection = null;
    protected $bindResult = null;
    protected $searchResult = null;
    protected $numEntries = 0;

    /**
     * Constructor sets up {$hostUrl, $hostPort}
     * optional {$baseDN, $ldapAttributes, $maxEntriesToGet}
     * in that order depending on num args.
     */
    function __construct($hostUrl, $hostPort, $baseDn, $ldapAttrib, $maxEntriesToGet) {
        $this->hostUrl = $hostUrl;
        $this->hostPort = (int) $hostPort;
        $this->baseDN = $baseDn;
        $this->ldapAttributes = explode(' ', $ldapAttrib);
        $this->maxEntriesToGet = (int) $maxEntriesToGet;
    }

    public function connect() {
        $this->ldapConnection = ldap_connect($this->hostUrl, $this->hostPort);
        $this->bindResult = @ldap_bind($this->ldapConnection);
        return $this->bindResult;
    }

    public function disconnect() {
        if ($ldapConnection) {
            @ldap_unbind($ldapConnection); //Assume success
        }
        $ldapConnection = null;
    }

    public function doSearch($filter) {
        $numEntries = 0;

        if (!$this->bindResult) {
            $this->connect();
        }
        @$this->searchResult = ldap_search($this->ldapConnection, $this->baseDN, $filter, $this->ldapAttributes);
        @$this->numEntries = ldap_count_entries($this->ldapConnection, $this->searchResult);

        return $this->numEntries;
    }

    public function getSearchEntries() {
        if ($this->ldapConnection && $this->searchResult && ($this->maxEntriesToGet >= $this->numEntries)) {
            ldap_sort($this->ldapConnection, $this->searchResult, 'sn');
            return ldap_get_entries($this->ldapConnection, $this->searchResult);
        } else
            return false;
    }

}

?>
