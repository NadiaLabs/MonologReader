import './index.scss';
import 'bootstrap/js/dist/modal';
import CodeMirror from 'codemirror';
import 'codemirror/mode/javascript/javascript';
import jsBeautify from 'js-beautify';

let codemirrorOptions = {
    lineWrapping: true,
    mode: {
        name: 'javascript',
        json: true,
        statementIndent: 2
    },
    theme: 'monokai'
};

$('.modal.codemirror-json').each(function() {
    let $modal = $(this);
    let $textarea = $modal.find('textarea');

    $textarea.html(jsBeautify($textarea.html(), { indent_size: 2, space_in_empty_paren: true }));
    CodeMirror.fromTextArea($textarea[0], codemirrorOptions);

    $modal.on('shown.bs.modal', function() {
        $(this).find('.CodeMirror')[0].CodeMirror.refresh();
    });
});
