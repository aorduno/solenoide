import DS from 'ember-data';

export default DS.Model.extend({
  file: DS.attr('string'),

  serialize: function () {
    var serialized = this._super.apply(this, arguments);
    return {
      file: serialized.file
    };
  }
});
