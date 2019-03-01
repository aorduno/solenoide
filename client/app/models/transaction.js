import DS from 'ember-data';

export default DS.Model.extend({
  date: DS.attr('string'),
  description: DS.attr('string'),
  amount: DS.attr('string'),
  ownerId: DS.attr('number')
});
