<?php

namespace helper;

class Breadcrumb
{
    /**
     *
     * @var Array
     */
    protected $nodes = array();


    /**
     * Add Node to breadcrumb
     *
     * @param string|array $text
     * @param string|array $link

     * @return Breadcrumb
     */
    public function add($text, $link = null)
    {
        if (is_string($text)) {
            $text = [$text];
        }
        if (!is_array($link)) {
            $link = [(string) $link];
        }
        foreach ($text as $key => $value) {
            $tmpLink = isset($link[$key]) ? $link[$key] : '';
            $this->nodes[$value] = (string) $tmpLink;
        }
        
        return $this;
    }

    public function getNodes()
    {
        return $this->nodes;
    }


    /**
     * Remove the node
     *
     * @param type $text
     * @return Breadcrumb
     */
    public function remove($text)
    {
        unset($this->nodes[$text]);
        return $this;
    }

        

    /**
     * Display the navigation
     *
     * @return string
     */
    public function __toString()
    {
        $pageStr = '<div id="breadcrumb"><i class="home-icon fa fa-home"></i>';
        $key = 1;

		foreach ($this->nodes as $title => $url) {

			if ($url != null) {
				$pageStr .= '<a href="' . htmlspecialchars($url) . '" >' . $title . '</a>';
			} else {
				$pageStr .= '<span>' . $title . '</span>';
			}
            $pageStr .= '<sup class="arrow-right">»</sup>';

            $key++;
		}
		return $pageStr . '</div>';

    }
}