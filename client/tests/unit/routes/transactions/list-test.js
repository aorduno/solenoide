import { module, test } from 'qunit';
import { setupTest } from 'ember-qunit';

module('Unit | Route | transactions/list', function(hooks) {
  setupTest(hooks);

  test('it exists', function(assert) {
    let route = this.owner.lookup('route:transactions/list');
    assert.ok(route);
  });
});
