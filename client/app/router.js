import EmberRouter from '@ember/routing/router';
import config from './config/environment';

const Router = EmberRouter.extend({
  location: config.locationType,
  rootURL: config.rootURL
});

Router.map(function() {
  this.route('transactions', function() {
    this.route('list');
    this.route('add');
  });
  this.route('dashboard');
});

export default Router;
