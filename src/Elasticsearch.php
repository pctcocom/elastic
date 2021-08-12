<?php
namespace Pctco\Elastic;
use Elasticsearch\ClientBuilder;
 /**
 * @name Elasticsearch
 * @describe Elasticsearch API
 * @author https://github.com/pctcocom/elastic
 **/
class Elasticsearch{
   function __construct(){
      $this->client = ClientBuilder::create()->build();

      // 索引目录信息
      $this->indexs = [
         'index' => 'my_index',
         'id'    => 'my_id'
      ];
   }
   /**
   * @name index
   * @describe 创建索引目录
   * @return Array
   **/
   public function index(){
      $params = array_merge([
         'body'  => [
            'settings' => [
               'number_of_shards' => 3,
               'number_of_replicas' => 2
            ],
            'mappings' => [
               '_source' => [
                  'enabled' => true
               ],
               'properties' => [
                  'first_name' => [
                     'type' => 'keyword'
                  ],
                  'age' => [
                     'type' => 'integer'
                  ]
               ]
            ]
         ]
      ],$this->indexs);

      $response = $this->client->index($params);
      return $response;
   }

   /**
   * @name get
   * @describe 获取索引目录
   * @return Array
   **/
   public function get(){
      try {
         $response = $this->client->get($this->indexs);
         return $response;
      } catch (\Exception $e) {
         return false;
      }
   }

   /**
   * @name update
   * @describe 更新索引字段
   * @return Array
   **/
   public function update(){
      $params = array_merge($this->indexs,[
         'body'  => [
            'doc' => [
               // 新字段
               'new_field' => [
                  'type' => 'integer'
               ]
            ]
         ]
      ]);
      $response = $this->client->update($params);
      return $response;
   }

   /**
   * @name getSettings
   * @describe 获取索引设置 API
   * @return Array
   **/
   public function getSettings(){
      $response = $this->client->indices()->getSettings(['index' => $this->indexs['index']]);
      return $response;
   }

   /**
   * @name Delete
   * @describe 删除文档 或 删除索引
   * @param mixed $del document(删除文档)、index(删除索引)
   * @return Array
   **/
   public function delete($del){
      try {
         if ($del === 'document') $response = $this->client->delete($this->indexs);
         if ($del === 'index') $response = $this->client->indices()->delete([
            'index'   =>   $this->indexs['index']
         ]);
         return $response;
      } catch (\Exception $e) {
         return true;
      }
   }

   /**
   * @name search
   * @describe 搜索文档
   * @return Array
   **/
   public function search(){
      $params = [
         'index' => $this->indexs['index'],
         'body'  => [
            'query' => [
               'match' => [
                   'testField' => 'abc'
               ]
            ]
         ]
      ];

      $response = $this->client->search($params);
      return $response;
   }

}
