TODO for the  InlineEditableWidgetsBundle
=========================================
- Securing: ajax actions utilizar los roles de admin y de editor...
- formulario de administrador
- completar ckeditor con ckfinder o el file manager...
- añadir opción de imágenes/ficheros al ckeditor

- CONFIGURACIONES DEL BUNDLE:
    - algo de css o js?
    - default values para content entity con cada tipo de content
    - configuraciones para ckeditor o file manager

- input de tipo file en formularios (las llamadas son ajax...)
- custom yml validator... para los campos yml
- custom form type ckeditor... ajax file upload... u otros...
- form styling including errors
- ver en content demo qué hacer si no existe en render o en collection el handler en base de datos... find en vez de findifnotexist
- Testar validator que los handlers y los container id sólo contienen a-z, 0-9, y - y _
- Validación para testar que están los js adecuados (tanto para el admin como para los renders) o un helper para añadirlos...
- crear combinaciones de opciones de ckeditor
- try catch del rendering de si existe el form_class y o el entity_class
- utilizar jcrop a la hora de subir la imagen
- usar futuras tooltips de jqueryui algo como doubleclick to edit con el cursor...
- como tratar los assets en los widgets...

- probar widgets de:
http://twitter.com/about/resources
http://developers.facebook.com/docs/plugins/
- un helper gist que meta todos los js y css necesarios... o un comprimido en .zip para que puedan descomprimirlo en web...
- pueden ser útiles:
     http://leocaseiro.com.br/jquery-plugin-string-to-slug/
     jalert
     jcrop o similar para recortar imágenes...

Comand line utils
-----------------
app/console doctrine:schema:drop --force
app/console doctrine:schema:create

git status
git add ...
git commit -m 'Commit description'
git push origin master

Js and css requisites
---------------------
- jquery por ahora 1.5 o superior
- jqueryui con dialog
- jquery-ui-fix-4727.js para arreglar los problemas con los inputs de los dialogs del ckeditor
- jquery theme
- jquery.blockUI.js
--To use the ckeditor and (kcfinder)
/ckeditor/ckeditor.js
/ckeditor/adapters/jquery.js



forms: possible render templates (first is default) option of applying callbacks...
  plain_text: escaped raw
  texarea: raw escaped
  wysiwyg: raw
  file(fichero, caption, link): image, escaped
  plain_text_list: escaped raw ul ol faq, jq_gfeed jq_media
  texarea_list: raw escaped ul ol faq jq_ui_tabs, jq_ui_accordion
  wysiwyg_list: raw ul ol faq
  file_list: image escaped image_ul image_ol jq_cycle  jq_hoverpulse  jq_colorbox

escaped, raw

form_params:
    'max_file_size'
    'thumbnail' thumb: { 'targetMime': 'image/gif', 'maxWidth': 300, 'maxHeight': 300, 'scale': true, 'inflate': true, 'quality': 75 }
    'allowed_mime_types'
    'max_items'

render_params:
    'apply_raw'
    'template_to_render'
    'jq_string'
    'max_items'

para cada widget, hacer un createDefaultContent, un getform y un render pudiendo sobreescribir los valores??

