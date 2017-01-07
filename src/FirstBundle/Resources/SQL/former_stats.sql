/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  zolano
 * Created: Aug 29, 2016
 */
SELECT t.item_id, t.iteration, t.user_id, k.step 
FROM tourXitem t 
LEFT JOIN kanban_item_step k 
ON k.item_id = t.item_id AND k.user_id = t.user_id AND k.iteration = t.iteration 
WHERE (t.field_id = 10) AND (t.iteration = 1) AND ( t.user_id = 1)