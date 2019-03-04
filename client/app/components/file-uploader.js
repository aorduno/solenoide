import Component from '@ember/component';
import {computed} from '@ember/object';

export default Component.extend({
  store: Ember.inject.service(),
  fullScreenInterstitialService: Ember.inject.service(),

  // tagName: 'form',
  csvContent: null,
  csvFileMeta: null,
  errorMessage: null,
  successMessage: null,
  hasError: computed('errorMessage', function () {
    return !Ember.isEmpty(this.get('errorMessage'));
  }),

  hasSuccessMessage: computed('successMessage', function () {
    return !Ember.isEmpty(this.get('successMessage'));
  }),

  uploadPhoto: function (file) {
    try {
      file.readAsDataURL().then(function (url) {
        console.log(url);
      });

      let self = this;
      let fullScreenInterstitialService = this.get('fullScreenInterstitialService');
      self.set('successMessage', null);
      self.set('errorMessage', null);
      fullScreenInterstitialService.show();
      file.upload('http://localhost:6969/api/transactionUploads', {
        data: {
          userId: 2
        },
      }).then(function (response) {
        self.set('successMessage', 'Your transaction has been uploaded, you will get a notification when it is processed');
      }).catch(function (response) {
        self.set('errorMessage', response.body.error);
      }).finally(function () {
        fullScreenInterstitialService.hide();
      });
    } catch (e) {
    }
  },

  actions: {
    uploadImage(file) {
      this.uploadPhoto(file);
    }
  }
});
