<?php

class IobySolrBaseQuery extends SolrBaseQuery
{
  /**
   * Exposes the base_path property from SolrBaseQuery
   *
   * @return string
   */
  public function getBasePath() {
    return $this->base_path;
  }
}
