<?php
namespace MissionNext\DB\SqlStatement;

abstract class SqlStatement {

   abstract  public function foreignKeyChecksOff();

   abstract  public function foreignKeyChecksOn();

   abstract public function truncateTable($tableName);

   abstract public function  groupConcat($fieldName, $alias = null);

} 