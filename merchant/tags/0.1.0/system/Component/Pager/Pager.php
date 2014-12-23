<?php

/**
 * xframework - 敏捷高效的php框架
 * 
 * @copyright xlight www.im87.cn
 * @license Please contact the author before using it.
 * @author xlight i@im87.cn
 */

namespace System\Component\Pager;

use System\Bootstrap;
use System\Component\Http;

 /**
  * Component: Pager
  * 
  * @author xlight <i@im87.cn>
  */
class Pager {
    
    /**
     * Current page number.
     * @var int
     */
    private $_page;
    
    /**
     * Pager options.
     * @var array
     */
    private $_params = array();
    
    public function __construct(array $params = array()) {
        $this->setParams($params);
    }
    
    /**
     * Set pager options.
     * 
     * @param array $params
     */
    public function setParams(array $params) {
        $this->_params = array_merge(array(
            'total'    => 0,
            'limit'    => 10,
            'quantity' => 7
        ), $params);
    }
    
    /**
     * Get current page number.
     */
    public function getPageNumber() {
        if (! isset($this->_page)) {
            $page = $_GET['page'];
            if (! is_numeric($page) || $page < 0) {
                $page = 0;
            }
            $this->_page = $page;
        }
        
        return $this->_page;
    }
    
    /**
     * Returns an HTML pager.
     * 
     * @param array $params
     * @return string
     */
    public function render($params = array()) {
        if (! empty($params)) {
            $this->setParams($params);
        }
        $total    = $this->_params['total'];
        $limit    = $this->_params['limit'];
        $quantity = $this->_params['quantity'];
        $page     = $this->getPageNumber();
    
        $pager_middle = ceil($quantity / 2);
        // current is the page we are currently paged to
        $pager_current = $page + 1;
        // first is the first page listed by this pager piece (re quantity)
        $pager_first = $pager_current - $pager_middle + 1;
        // last is the last page listed by this pager piece (re quantity)
        $pager_last = $pager_current + $quantity - $pager_middle;
        // max is the maximum page number
        $pager_max = ceil($total / $limit);
        // End of marker calculations.
    
        // Prepare for generation loop.
        $i = $pager_first;
        if ($pager_last > $pager_max) {
            // Adjust "center" if at end of query.
            $i = $i + ($pager_max - $pager_last);
            $pager_last = $pager_max;
        }
        if ($i <= 0) {
            // Adjust "center" if at start of query.
            $pager_last = $pager_last + (1 - $i);
            $i = 1;
        }
        // End of generation loop preparation.
    
        $li_first = $page > 0 ? '<a href="' . $this->getUrl(1) . '">' . t('first') . '</a>' : '';
        $li_previous = $page > 0 ? '<a href="' . $this->getUrl($page) . '">' . t('previous') . '</a>' : '';
        $li_next = $page < $pager_max - 1 ? '<a href="' . $this->getUrl($page + 2) . '">' . t('next') . '</a>' : '';
        $li_last = $page < $pager_max - 1 ? '<a href="' . $this->getUrl($pager_max) . '">' . t('last') . '</a>' : '';
    
        if ($pager_max > 1) {
            if ($li_first) {
                $items[] = array(
                    'class' => 'pager-first',
                    'data' => $li_first,
                );
            }
            if ($li_previous) {
              $items[] = array(
                'class' => 'pager-previous',
                'data' => $li_previous,
              );
            }
        
            // When there is more than one page, create the pager list.
            if ($i != $pager_max) {
                if ($i > 1) {
                    $items[] = array(
                        'class' => 'pager-ellipsis',
                        'data' => '…',
                    );
                }
                // Now generate the actual pager piece.
                for (; $i <= $pager_last && $i <= $pager_max; $i++) {
                    if ($i < $pager_current) {
                        $items[] = array(
                            'class' => 'pager-item',
                            'data' => '<a href="' . $this->getUrl($i) . '">' . $i . '</a>',
                        );
                    } elseif ($i == $pager_current) {
                        $items[] = array(
                            'class' => 'pager-current',
                            'data' => $i,
                        );
                    } elseif ($i > $pager_current) {
                        $items[] = array(
                            'class' => 'pager-item',
                            'data' => '<a href="' . $this->getUrl($i) . '">' . $i . '</a>',
                        );
                    }
                }
                if ($i < $pager_max) {
                    $items[] = array(
                        'class' => 'pager-ellipsis',
                        'data' => '…',
                    );
                }
            }
            // End generation.
            if ($li_next) {
                $items[] = array(
                    'class' => 'pager-next',
                    'data' => $li_next,
                );
            }
            if ($li_last) {
                $items[] = array(
                    'class' => 'pager-last',
                    'data' => $li_last,
                );
            }
        
            $html = '<ul class="pager">';
            foreach ($items as $item) {
                $html .= '<li class="' . $item['class'] . '">' . $item['data'] . '</li>';
            }
            $html .= '</ul>';
            
            return $html;
        }
    }
    
    /**
     * Make url for pager items.
     * 
     * @param int $page Item page number.
     * @return string
     */
    private function getUrl($page) {
        $page = (int) $page;
        $url = Http\Request::urlInfo();
        if ($page === 1) {
            unset($url['query']['page']);
        } else {
            $url['query']['page'] = $page - 1;
        }
        
        return url($url['path'], array('query' => $url['query']));
    }
}
