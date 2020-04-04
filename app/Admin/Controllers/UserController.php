<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\User\Info;
use App\Repositories\User;
use App\Repositories\UserInfo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Repositories\User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('sn', __('Sn'));
        $grid->column('name', __('Name'))->display(function(){
            return $this->info(UserInfo::ITEM_NAME, 'data');
        });
        $grid->column('ts', __('Ts'));

        $grid->disableCreateButton();

        $grid->actions(function($action) {
            $action->add(new Info);
        });

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sn', __('Sn'))->reshow();
        $show->account('Account')->as(function($name){
            return $this->info(UserInfo::ITEM_ACCOUNT, 'data');
        });
        $show->name('Name')->as(function($name){
            return $this->info(UserInfo::ITEM_NAME, 'data');
        });
        $show->info('Info', function($info){
            $info->model()->whereIn('item', [1,3]);
            $info->resource('/admin/user-info');
            $info->item()->display(function($item){
                return UserInfo::ITEM_DICT[$item];
            });
            $info->data();
            $info->status();
        });
      
        $show->field('ts', __('Ts'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('sn', __('Sn'));
        $form->text('salt', __('Salt'));
        $form->datetime('ts', __('Ts'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
