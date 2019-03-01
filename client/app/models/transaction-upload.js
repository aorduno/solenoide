import DS from 'ember-data';

export default DS.Model.extend({
  filename: DS.attr('string'),
  status: DS.attr('string'),
  completed: DS.attr('number'),
  failed: DS.attr('number')
});
