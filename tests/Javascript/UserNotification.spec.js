jest.mock('axios', () => ({
    get: jest.fn(() => Promise.resolve([
        {id: 1, data: {message: 'Test message 1', link: '/test/link/1'}},
        {id: 2, data: {message: 'Test message 2', link: '/test/link/2'}}
    ])),
    delete: jest.fn(() => Promise.resolve())
}));

import axios from 'axios'
import {shallow} from '@vue/test-utils';
import UserNotification from '../../resources/assets/js/components/UserNotifications.vue';

describe('UserNotification', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = shallow(UserNotification, {
            propsData: {
                userName: 'testUser'
            }
        });
        jest.resetModules();
        jest.clearAllMocks();
    });

    afterEach(() => {
    });

    describe('#created', () => {
        it('equals prop userName to testUser', function () {
            expect(wrapper.vm.userName).toEqual('testUser');
        });

        it('should render the notifications', function () {
            const notificationsWrapper = wrapper.findAll('.dropdown-menu a'),
                firstNotificationWrapper = notificationsWrapper.at(0),
                secondNotificationWrapper = notificationsWrapper.at(1);

            expect(firstNotificationWrapper.attributes().href).toBe('/test/link/1');
            expect(firstNotificationWrapper.text()).toBe('Test message 1');

            expect(secondNotificationWrapper.attributes().href).toBe('/test/link/2');
            expect(secondNotificationWrapper.text()).toBe('Test message 2');
        });
    });

    describe('#getNotifications',() => {

        it('Calls axios.get and receives notifications', async () => {
            await wrapper.vm.getNotifications();


            expect(wrapper.vm.notifications).toEqual([
                {id: 1, data: {message: 'Test message 1', link: '/test/link/1'}},
                {id: 2, data: {message: 'Test message 2', link: '/test/link/2'}}
            ]);
            expect(axios.get).toBeCalledWith('/profiles/testUser/notifications')
        })

    });

    describe('#markAsRead', () => {
        beforeEach(() => {
            wrapper.find('.dropdown-menu a').trigger('click');
        });

        it('should delete the notification', () => {
            expect(axios.delete).toBeCalledWith('/profiles/testUser/notifications/1');
        });

        it('should remove the clicked notification from list', async () => {
            await wrapper.vm.$nextTick();
            expect(wrapper.vm.notifications.length).toBe(1);
        })
    });
});
