<?php

function guild_update($pdo, $guild_id, $guild_name, $emblem, $note, $owner_id, $member_count = NULL)
{
    // check for member count passed to this function
    $memcount_sql = '';
    if ($member_count != NULL) {
        $member_count = (int) $member_count;
        $memcount_sql = 'member_count = :member_count,';
    }
    
    // do it
    $stmt = $pdo->prepare('
        UPDATE guilds
           SET guild_name = :name,
               $memcount_sql
               emblem = :emblem,
               note = :note,
               owner_id = :owner_id
         WHERE guild_id = :guild_id
         LIMIT 1
    ');
    $stmt->bindValue(':name', $guild_name, PDO::PARAM_STR);
    $stmt->bindValue(':emblem', $emblem, PDO::PARAM_STR);
    $stmt->bindValue(':note', $note, PDO::PARAM_STR);
    $stmt->bindValue(':owner_id', $owner_id, PDO::PARAM_INT);
    $stmt->bindValue(':guild_id', $guild_id, PDO::PARAM_INT);
    if ($member_count != NULL) {
        $stmt->bindValue(':member_count', $member_count, PDO::PARAM_INT);
    }
    $result = $stmt->execute();
    
    if ($result === false) {
        throw new Exception('Could not update guild.');
    }
    
    return $result;
}
