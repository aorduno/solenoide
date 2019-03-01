import ApplicationAdapter from './application';
import Inflector from 'ember-inflector'

const localInflector = Inflector.inflector;

export default ApplicationAdapter.extend({
  init() {
    this._super(...arguments);

    this.set('headers', {
      'content-type': 'multipart/form-data'
    });
  },

  // convert from file-upload-request to files
  pathForType(type) {
    return 'transactionUploads'
  },
});
