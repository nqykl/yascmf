<?php namespace Douyasi\Http\Requests;

use Douyasi\Http\Requests\DouyasiRequest;

class PageRequest extends DouyasiRequest
{


    /**
     * 自定义验证规则rules
     *
     * @param string $type 规则类型，'store'表示存储数据时规则，'update'表示更新数据时规则，'destroy'表示删除数据时规则（极少用到）
     * @return array
     */
    public function rules($type = 'store')
    {
        $rules = [
            'title'    => 'required|max:80',
            'content' => 'required|min:20',
            'is_top'  => 'boolean',
            'outer_link'  => 'url_link',
            'thumb'   => 'self_url',
        ];
        if ($type === 'update') {  //
            $id = $this->segment(3) ? ',' . $this->segment(3) : '';
            $rules = array_add($rules, 'slug', 'required|max:30|eng_alpha_dash|unique:contents,slug'.$id);
            //slug在添加时不予展示，修改时予以展示
        }
        return $rules;
    }

    /**
     * 自定义验证信息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required'   => '请填写单页标题',
            'title.max'         => '单页标题过长，建议长度不要超出60',
            'slug.required'    => '请填写缩略名',
            'slug.max'          => '缩略名过长',
            'slug.unique'      => '已有同名缩略名',
            'slug.eng_alpha_dash'  => '缩略名只能数字、字母、下划线与横杠(0-9A-Za-z_-)组合',
            'content.required' => '请填写单页正文',
            'content.min'      => '单页正文过短，长度不得少于20',
            'is_top.boolean'   => '是否置顶必须为布尔值',
            'outer_link.url_link' => '外链地址不合法',
            'thumb.self_url'    => '缩略图地址必须在当前域名下',
        ];
    }

    /**
     * 自定义响应
     *
     * @return array 返回的数组将被JSON化作为响应
     */
    public function response()
    {
        return [
            'status' => 0,
            'info' => '失败',
            'operation' => '单页操作',
            'url' => route('admin.page.index'),
        ];
    }
}
