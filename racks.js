function racks_initJs(root_doc) {
    this.root_doc = root_doc;
}

/**
 *
 * @param action
 * @param toobserve
 * @param toupdate
 */
this.racks_synchronize = function (options) {

    var object = this;
    var name = $('#' + options.name).val();

    $.ajax({
         url: object.root_doc + '/plugins/racks/ajax/synchronize_device.php',
         type: "POST",
         dataType: "json",
         data: 'action=racks_synchronize' +
         '&update_server=' + options.update_server +
         '&id=' + options.id +
         '&faces_id=' + options.faces_id +
         '&plugin_racks_itemspecifications_id=' + options.plugin_racks_itemspecifications_id +
         '&items_id=' + options.items_id +
         '&type=' + options.type +
         '&rack_size=' + options.rack_size +
         '&position=' + options.position +
         '&plugin_racks_racks_id=' + options.plugin_racks_racks_id +
         '&name=' + name,
         success: function (data) {
            window.location.reload();
         }
      });
};
