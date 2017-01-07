
-- SQL VIEW qui prévoit la jointure entre user, tour, link_tour_item--
CREATE VIEW view_link_user_item AS 
SELECT t.id as tour_id, t.iteration as iteration, u.id as user_id, u.login as user, li.item_id as item_id, li.done as done
FROM `tour` t 
LEFT JOIN link_tour_item li ON li.tour_id = t.id
LEFT JOIN user u ON t.user_id = u.id

-- SQL VIEW qui prévoit la jointure entre user, field, link_tour_field --
CREATE VIEW view_link_user_field AS 
SELECT t.id as tour_id, t.iteration as iteration, u.id as user_id, u.login as user, lf.field_id as field_id, lf.done as done
FROM `tour` t 
LEFT JOIN link_tour_field lf ON lf.tour_id = t.id
LEFT JOIN user u ON t.user_id = u.id

-- SQL VIEW qui regroupe une ligne pour chaque couple itemXfield, + done (de item)
CREATE VIEW tourXitem AS
SELECT t.id as tour_id, t.iteration as iteration, t.workset_id as workset_id, li.item_id as item_id, i.field_id as field_id, li.done as done, u.id as user_id, u.login as login
FROM  tour t 
LEFT JOIN link_tour_item li ON li.tour_id = t.id
RIGHT JOIN item i ON li.item_id = i.id
LEFT JOIN user u ON u.id = t.user_id
ORDER BY user_id, iteration

-- SQL VIEW qui regroupe une ligne de jointure pour un item, un tour, le kanban associé, le step, son nom et son état (done ou non)
CREATE VIEW kanbanXitem AS
SELECT t.item_id, i.name as item_name, t.iteration, t.field_id , t.user_id, k.step, ti.done
FROM tourXitem t 
LEFT JOIN kanban_item_step k ON k.item_id = t.item_id AND k.user_id = t.user_id AND k.iteration = t.iteration 
LEFT JOIN item i ON t.item_id = i.id 
LEFT JOIN link_tour_item ti ON t.tour_id = ti.tour_id AND t.user_id = ti.user_id AND t.item_id = ti.item_id