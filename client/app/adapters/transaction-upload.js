import ApplicationAdapter from './application';

export default ApplicationAdapter.extend({
  pathForType(type) {
    return 'transactionUploads';
  },
});
