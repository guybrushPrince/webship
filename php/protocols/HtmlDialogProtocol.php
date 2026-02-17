<?php

namespace webship\protocols;

use Event;
use ship\protocols\DialogProtocol;
use webship\dialogs\DialogHtmlView;

/**
 * Class HtmlDialogProtocol
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 */
class HtmlDialogProtocol extends DialogProtocol {

    /**
     * @inheritDoc
     */
    public function __construct($parent, $texts, $options) {
        parent::__construct($parent, $texts, $options, HtmlModifyProtocol::class);
    }

    /**
     * @inheritDoc
     */
    public function generate() : string {
        $texts = $this->texts;
        $options = $this->options;

        $v = new DialogHtmlView($texts);
        $v->ref('dialog');
        foreach ($options as $key => $texts) {
            $v->choice($key, $texts);
        }
        $code = '$("body").append(\'' . $v->generate() . '\');';

        // Generate code for the actions.
        $code .= implode('', array_map(function (array $p) use ($v) {
            $choice = $v->getChoice($p["number"]);
            $protocol = new HtmlEventProtocol(Event::CLICK, $choice);
            $protocol->modify()->delete($v)->append($p["protocol"]);
            return $protocol->generate();
        }, $this->protocol));

        return $code;
    }
}
?>