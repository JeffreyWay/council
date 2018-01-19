import {mount} from 'vue-test-utils';
import expect from 'expect';
import requestService from '../../resources/assets/js/services/requestService';
import sinon from 'sinon';
import UserNotification from '../../resources/assets/js/components/UserNotifications.vue';

describe('UserNotification', () => {
    let wrapper;

    const firstNotification = {id: 1, data: {message: 'Test message 1', link: '/test/link/1'}},
        secondNotification = {id: 2, data: {message: 'Test message 2', link: '/test/link/2'}},
        notifications = [firstNotification, secondNotification],
        testUser = 'testUser';

    beforeEach(() => {
        sinon.stub(requestService, 'get').returns(Promise.resolve(notifications));
        sinon.stub(requestService, 'delete').returns(Promise.resolve());
        wrapper = mount(UserNotification, {
            propsData: {
                userName: testUser
            }
        });
    });

    afterEach(() => {
        requestService.get.restore();
        requestService.delete.restore();
    });

    describe('#created', () => {
        it('should get notifications', () => {
            expect(wrapper.vm.notifications).toEqual(notifications);
        });

        it('should render the notifications', function () {
            const notificationsWrapper = wrapper.findAll('.dropdown-menu a'),
                firstNotificationWrapper = notificationsWrapper.at(0),
                secondNotificationWrapper = notificationsWrapper.at(1);

            expect(firstNotificationWrapper.attributes().href).toBe(firstNotification.data.link);
            expect(firstNotificationWrapper.text()).toBe(firstNotification.data.message);

            expect(secondNotificationWrapper.attributes().href).toBe(secondNotification.data.link);
            expect(secondNotificationWrapper.text()).toBe(secondNotification.data.message);
        });
    });

    describe('#markAsRead', () => {
        beforeEach(() => {
            wrapper.find('.dropdown-menu a').trigger('click');
        });

        it('should delete the notification', () => {
            expect(requestService.delete.called).toBe(true);
            expect(requestService.delete.getCall(0).args[0]).toBe(`/profiles/${testUser}/notifications/${firstNotification.id}`);
        });

        it('should remove the clicked notification from list', () => {
            wrapper.vm.$nextTick().then(() => expect(wrapper.vm.notifications.length).toBe(1));
        })
    });
});
