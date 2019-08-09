<?php

class erLhcoreClassChatArcive {

    public static function getList($paramsSearch = array(), $class = 'erLhcoreClassModelChatArchive', $tableName = 'lh_chat')
    {
	       $paramsDefault = array('limit' => 32, 'offset' => 0);

	       $params = array_merge($paramsDefault,$paramsSearch);

	       $session = erLhcoreClassChat::getSession();
	       $q = $session->createFindQuery( $class );

	       $conditions = array();

	       if (!isset($paramsSearch['smart_select'])) {
			      if (isset($params['filter']) && count($params['filter']) > 0)
			      {
			           foreach ($params['filter'] as $field => $fieldValue)
			           {
			               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
			           }
			      }

			      if (isset($params['filterin']) && count($params['filterin']) > 0)
			      {
			           foreach ($params['filterin'] as $field => $fieldValue)
			           {
			               $conditions[] = $q->expr->in( $field, $fieldValue );
			           }
			      }

			      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
			      {
			           foreach ($params['filterlt'] as $field => $fieldValue)
			           {
			               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
			           }
			      }

			      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
			      {
			           foreach ($params['filtergt'] as $field => $fieldValue)
			           {
			               $conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue ));
			           }
			      }

			      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
			      {
				       foreach ($params['filterlte'] as $field => $fieldValue)
				       {
				      		$conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
				       }
			      }

			      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
			      {
				      	foreach ($params['filtergte'] as $field => $fieldValue)
				      	{
				      		$conditions[] = $q->expr->gte( $field,$q->bindValue( $fieldValue ));
				      	}
				  }

			      if (isset($params['customfilter']) && count($params['customfilter']) > 0)
			      {
				      	foreach ($params['customfilter'] as $fieldValue)
				      	{
				      		$conditions[] = $fieldValue;
				      	}
			      }

			      if (count($conditions) > 0)
			      {
			          $q->where(
			                     $conditions
			          );
			      }

				 if (isset($params['use_index'])) {
		      		$q->useIndex( $params['use_index'] );
		      	 }

			      $q->limit($params['limit'],$params['offset']);

			      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' );
	      } else {

		      	$q2 = $q->subSelect();
		      	$q2->select( 'id' )->from( $tableName );

		      	if (isset($params['filter']) && count($params['filter']) > 0)
		      	{
		      		foreach ($params['filter'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->eq( $field, $q->bindValue($fieldValue) );
		      		}
		      	}

		      	if (isset($params['filterin']) && count($params['filterin']) > 0)
		      	{
		      		foreach ($params['filterin'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->in( $field, $fieldValue );
		      		}
		      	}

		      	if (isset($params['filterlt']) && count($params['filterlt']) > 0)
		      	{
		      		foreach ($params['filterlt'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->lt( $field, $q->bindValue($fieldValue) );
		      		}
		      	}

		      	if (isset($params['filterlte']) && count($params['filterlte']) > 0)
		      	{
		      		foreach ($params['filterlte'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->lte( $field, $q->bindValue($fieldValue) );
		      		}
		      	}

		      	if (isset($params['filtergt']) && count($params['filtergt']) > 0)
		      	{
		      		foreach ($params['filtergt'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->gt( $field,$q->bindValue( $fieldValue) );
		      		}
		      	}

		      	if (isset($params['filtergte']) && count($params['filtergte']) > 0)
		      	{
		      		foreach ($params['filtergte'] as $field => $fieldValue)
		      		{
		      			$conditions[] = $q2->expr->gte( $field,$q->bindValue( $fieldValue) );
		      		}
		      	}

		      	if (isset($params['customfilter']) && count($params['customfilter']) > 0)
		      	{
		      		foreach ($params['customfilter'] as $fieldValue)
		      		{
		      			$conditions[] = $fieldValue;
		      		}
		      	}


		      	if (count($conditions) > 0)
		      	{
		      		$q2->where(
		      				$conditions
		      		);
		      	}

		      	if (isset($params['use_index'])) {
		      		$q2->useIndex( $params['use_index'] );
		      	}

		      	$q2->limit($params['limit'],$params['offset']);
		      	$q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC');

		      	$q->innerJoin( $q->alias( $q2, 'items' ), $tableName . '.id', 'items.id' );
		      	$q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' );
	      }

	      $objects = $session->find( $q );

	      return $objects;
    }



    public static function getCount($params = array(), $table = 'lh_chat', $operation = 'COUNT(id)')
    {
    	$session = erLhcoreClassChat::getSession();
    	$q = $session->database->createSelectQuery();
    	$q->select( $operation )->from( $table );
    	$conditions = array();

    	if (isset($params['filter']) && count($params['filter']) > 0)
    	{
    		foreach ($params['filter'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
    		}
    	}

    	if (isset($params['filterin']) && count($params['filterin']) > 0)
    	{
    		foreach ($params['filterin'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->in( $field, $fieldValue );
    		}
    	}

    	if (isset($params['filterlt']) && count($params['filterlt']) > 0)
    	{
    		foreach ($params['filterlt'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
    		}
    	}

    	if (isset($params['filtergt']) && count($params['filtergt']) > 0)
    	{
    		foreach ($params['filtergt'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue ));
    		}
    	}

    	if (isset($params['filterlte']) && count($params['filterlte']) > 0)
    	{
    		foreach ($params['filterlte'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
    		}
    	}

    	if (isset($params['filtergte']) && count($params['filtergte']) > 0)
    	{
    		foreach ($params['filtergte'] as $field => $fieldValue)
    		{
    			$conditions[] = $q->expr->gte( $field,$q->bindValue( $fieldValue ));
    		}
    	}

    	if (isset($params['customfilter']) && count($params['customfilter']) > 0)
    	{
    		foreach ($params['customfilter'] as $fieldValue)
    		{
    			$conditions[] = $fieldValue;
    		}
    	}

    	if ( count($conditions) > 0 )
    	{
	    	$q->where( $conditions );
    	}

    	if (isset($params['use_index'])) {
    		$q->useIndex( $params['use_index'] );
    	}

    	$stmt = $q->prepare();
    	$stmt->execute();
    	$result = $stmt->fetchColumn();

    	return $result;
   }

   public static $archiveTable;
   public static $archiveMsgTable;
}

?>