<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->column('id', __('Id'));
        $grid->column('billno', __('Billno'));
        $grid->column('user_id', __('User id'));
        $grid->column('status', __('Status'));
        $grid->column('price', __('Price'));
        $grid->column('unit_id', __('Unit id'));
        $grid->column('home_address', __('Home address'));
        $grid->column('arrive_address', __('Arrive address'));
        $grid->column('get_time', __('Get time'));
        $grid->column('admin_id', __('Admin id'));
        $grid->column('pay_time', __('Pay time'));
        $grid->column('arrive_time', __('Arrive time'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('billno', __('Billno'));
        $show->field('user_id', __('User id'));
        $show->field('status', __('Status'));
        $show->field('price', __('Price'));
        $show->field('unit_id', __('Unit id'));
        $show->field('home_address', __('Home address'));
        $show->field('arrive_address', __('Arrive address'));
        $show->field('get_time', __('Get time'));
        $show->field('admin_id', __('Admin id'));
        $show->field('pay_time', __('Pay time'));
        $show->field('arrive_time', __('Arrive time'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('billno', __('Billno'));
        $form->number('user_id', __('User id'));
        $form->number('status', __('Status'));
        $form->number('price', __('Price'));
        $form->number('unit_id', __('Unit id'));
        $form->text('home_address', __('Home address'));
        $form->text('arrive_address', __('Arrive address'));
        $form->datetime('get_time', __('Get time'))->default(date('Y-m-d H:i:s'));
        $form->number('admin_id', __('Admin id'));
        $form->datetime('pay_time', __('Pay time'))->default(date('Y-m-d H:i:s'));
        $form->text('arrive_time', __('Arrive time'));

        return $form;
    }
}
