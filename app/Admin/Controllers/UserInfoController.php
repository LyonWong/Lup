<?php

namespace App\Admin\Controllers;

use App\Repositories\UserInfo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserInfoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Repositories\UserInfo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserInfo());

        $grid->model()->whereIn('item', [1]);

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('item', __('Item'))->using(UserInfo::ITEM_DICT);
        $grid->column('data', __('Data'));
        $grid->column('status', __('Status'));
        $grid->column('create_ts', __('Create ts'));
        $grid->column('update_ts', __('Update ts'));

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
        $show = new Show(UserInfo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('item', __('Item'));
        $show->field('data', __('Data'));
        $show->field('status', __('Status'));
        $show->field('create_ts', __('Create ts'));
        $show->field('update_ts', __('Update ts'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserInfo());

        $form->number('user_id', __('User id'));
        $form->number('item', __('Item'));
        $form->text('data', __('Data'));
        $form->switch('status', __('Status'));
        $form->datetime('create_ts', __('Create ts'))->default(date('Y-m-d H:i:s'));
        $form->datetime('update_ts', __('Update ts'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
