import DS from 'ember-data';

export default DS.JSONAPISerializer.extend({
  serialize(snapshot, options) {
    let json = this._super(...arguments);
    let formData = new FormData();
    formData.append('file', json.data.attributes.file);
    return formData;
  },
});
