<?php

namespace FirstBundle\Repository;

use Zolano\FluxinBundle\Repository\InfluxRepository;
use Oft\Mvc\Application;
use Oft\Db\EntityQueryBuilder;
use Oft\Entity\BaseEntity;
use Oft\Mvc\Helper\Json;

use Doctrine\ORM\EntityManager;

class StatsRepository{

    /**
     * @var EntityManager
     */
    protected $_em;

    protected $tourSQLview;

    protected $linkTourField;


    /**
     * InfluxRepository constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em){
        
        $this->_em = $em;
        $this->tourSQLview = 'tourXitem';
        $this->linkTourField = 'link_tour_field';

    }

    /** Fonction qui renvoie structurée l'ensemble des données de stats de l'avancement du tour et du workset en param pour l'uitlisateur donné
     * @param $workset
     * @param $iteration
     * @param $user_id
     */
    public function getWorksetGlobal($workset, $iteration, $user_id){

        $global_stats = array(
            'fields_stats' => [],
            'items' => array(
                'total',
                'done',
                'percentage',
            ),
            'fields' => array(
                'total',
                'done',
            ),
        );

        //Field Stats
        $fields_stats = array();
        foreach($workset->getFields() as $field){
//            dump($field);die;
            $fields_stats[$field->getId()] = $this->getFieldStatData($field, $iteration, $user_id);
        }

        //workset_stats
            //items
            $items_stats = $this->getWorksetStatsData($workset->getId(), $iteration, $user_id);

            //fields
            $fields_data = $this->getFieldsData($workset, $iteration, $user_id);

        $global_stats['fields_stats'] = $fields_stats;
        $global_stats['items'] = $items_stats;
        $global_stats['fields'] = $fields_data;

        return $global_stats;
    }

    /** Renvoie le nombre total de FIelds et le nombre de fields terminés pour le workset & l'itération en param,
     * @param $workset
     * @param $iteration
     * @param $user_id
     */
    public function getFieldsData($workset, $iteration, $user_id){

        $fieldIds = array();

        foreach($workset->getFields() as $field){
            $fieldIds[] = $field->getId();
        }
        //on implode les ID pour pouvoir les meyyre dans une condition SQL
        $fieldsIdString = implode(",", $fieldIds);

        $count_global = count($fieldIds);

        $qb = $this->_em->getConnection()->createQueryBuilder();

        $query_done = $qb   ->select('count(distinct(field_id)) as count')
                            ->from($this->linkTourField, 'lt')
                            ->leftJoin('lt', 'tour','t','lt.tour_id = t.id')
                            ->where('lt.field_id IN (' . $fieldsIdString . ')')
                            ->andWhere('lt.user_id  = ' . $user_id)
                            ->andWhere('t.iteration = ' . $iteration);

        $count_done = $query_done->execute()->fetch();



        return array(
            'total' => "$count_global",
            'done'  => $count_done['count'],
        );
    }

    /** Renvoie le nombre d'items total, terminés & le pourcentage pour l'ensemble du workset
     * @param $workset_id
     * @param $iteration
     * @param $user_id
     * @return array
     */
    public function getWorksetStatsData($workset_id, $iteration, $user_id){

        $qb = $this->_em->getConnection()->createQueryBuilder();

        //nombre total d'items
        $query_global = $qb ->select('count(item_id) as count')
                            ->from($this->tourSQLview, 'v')
                            ->where('workset_id = ' . $workset_id)
                            ->andWhere('iteration = ' . $iteration)
                            ->andWhere('user_id = ' . $user_id);

        $count_global = $query_global->execute()->fetch();

        $query_done = $qb   ->select('count(item_id) as count')
                            ->from($this->tourSQLview, 'v')
                            ->where('workset_id = ' . $workset_id)
                            ->andWhere('iteration = ' . $iteration)
                            ->andWhere('done = 1')
                            ->andWhere('user_id = ' . $user_id);

        $count_done = $query_done->execute()->fetch();

        //valeur pas défaut pour éviter les fatalerror
        $global = (isset($count_global['count'])) ? $count_global['count'] : 1 ;
        $done = (isset($count_done['count'])) ? $count_done['count'] : 0 ;


//        dump($count_global);
//        dump($count_done);
//        die;
////        dump($done);
////        dump($global);
//        dump("$done / $global");

        $percent = ($global != 0) ? round((($done / $global) * 100), 1) : 0 ;

        return array(
            'total'         => $global,
            'done'          => $done,
            'percentage'    => $percent,
        );
    }


    /** Renvoie pour la matière & le tour passée en paramètre pour l'user donné:
     *
     * @param $field
     * @output $data[
     *                  - nb total items
     *                  - nb items terminés
     *                  - % calculé
     * ]
     */
    public function getFieldStatData($field, $iteration, $user_id){

        $qb = $this->_em->getConnection()->createQueryBuilder();

        //nombre total d'items
        $query_global = $qb ->select('count(item_id) as count')
                            ->from($this->tourSQLview, 'v')
                            ->where('field_id = ' . $field->getId())
                            ->andWhere('iteration = ' . $iteration)
                            ->andWhere('user_id = ' . $user_id);

        $count_global = $query_global->execute()->fetch();

        $query_done = $qb   ->select('count(item_id) as count')
                            ->from($this->tourSQLview, 'v')
                            ->where('field_id = ' . $field->getId())
                            ->andWhere('iteration = ' . $iteration)
                            ->andWhere('done = 1')
                            ->andWhere('user_id = ' . $user_id);

        $count_done = $query_done->execute()->fetch();

        //valeur pas défaut pour éviter les fatalerror
        $global = (isset($count_global['count'])) ? $count_global['count'] : 0;
        $done = (isset($count_done['count'])) ? $count_done['count'] : 0 ;


//        dump($count_global);
//        dump($count_done);
//        dump($done);
//        dump($global);
//        dump("$done / $global");die;

        $percent = ($global != 0) ? round((($done / $global) * 100), 1) : 0 ;


        $fieldname = $field->getName();

        return array(
            'field_name'    => $fieldname,
            'items_global'  => $global,
            'color'         => $field->getColor(),
            'items_done'    => $done,
            'percentage'    => $percent,
        );

    }

    public function getLastIteration($workset_id, $user_id){
        $qb = $this->_em->getConnection()->createQueryBuilder();

        $query = $qb    ->select('MAX(iteration)')
                        ->from($this->tourSQLview, 'v')
                        ->where('workset_id = ' . $workset_id)
                        ->andWhere('user_id = ' . $user_id);

        $max_it = $query->execute()->fetchAll();

        return $max_it;
    }

    /** Instancie un Influxrepository
     *
     * @return InfluxRepository
     */
    private function getInfluxRepository(){

        return  new InfluxRepository($this->getEntityManager());


    }
}


