import Component from '@ember/component';
import {computed} from '@ember/object';

export default Component.extend({
  title: null,
  transactionUploadList: null,
  hasTransactions: computed('transactionUploadList.[]', function () {
    return this.get('transactionUploadList.length') > 0;
  })
});
