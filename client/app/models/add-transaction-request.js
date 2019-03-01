import DS from 'ember-data';

export default DS.Model.extend({
  fileContent: DS.attr('string')
});
