<?php
/**
 * Campsite customized Smarty plugin
 * @package Campsite
 */


/**
 * Campsite list_subtitles block plugin
 *
 * Type:     block
 * Name:     list_subtitles
 * Purpose:  Provides a...
 *
 * @param string
 *     $p_params
 * @param string
 *     $p_smarty
 * @param string
 *     $p_content
 *
 * @return
 *
 */
function smarty_block_list_subtitles($p_params, $p_content, &$p_smarty, &$p_repeat)
{
    require_once $p_smarty->_get_plugin_filepath('shared','escape_special_chars');

    // gets the context variable
    $campContext = $p_smarty->get_template_vars('campsite');
    $html = '';

    if (!isset($p_content)) {
        $start = $campContext->next_list_start('SubtitlesList');
        $subtitlesList = new SubtitlesList($start, $p_params);
        $campContext->setCurrentList($subtitlesList, array('subtitle'));
    }
    static $subtitleURLId = null;

    $currentSubtitle = $campContext->current_subtitles_list->current;
    if (is_null($currentSubtitle)) {
        $campContext->url->reset_parameter($subtitleURLId);
        $p_repeat = false;
        $campContext->resetCurrentList();
        return $html;
    } else {
        $campContext->subtitle = $currentSubtitle;
        $subtitleURLId = $campContext->article->subtitle_url_id($currentSubtitle->field_name);
        $campContext->url->set_parameter($subtitleURLId, $currentSubtitle->number);
        $p_repeat = true;
    }

    if (isset($p_content)) {
        $html = $p_content;
        if ($p_repeat) {
            $campContext->current_subtitles_list->defaultIterator()->next();
            if (!is_null($campContext->current_subtitles_list->current)) {
                $campContext->subtitle = $campContext->current_subtitles_list->current;
                $subtitleURLId = $campContext->article->subtitle_url_id($campContext->subtitle->field_name);
                $campContext->url->set_parameter($subtitleURLId, $campContext->subtitle->number);
            }
        }
    }

    return $html;
}

?>