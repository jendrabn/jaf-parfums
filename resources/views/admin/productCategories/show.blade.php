@extends('layouts.admin')
@section('content')
  <div class="card">
    <div class="card-header">
      {{ __('show') }} {{ __('Categories') }}
    </div>

    <div class="card-body">
      <div class="form-group">
        <div class="form-group">
          <a class="btn btn-default"
            href="{{ route('admin.product-categories.index') }}">
            {{ __('Back to list') }}
          </a>
        </div>
        <table class="table-bordered table-striped table">
          <tbody>
            <tr>
              <th>
                {{ __('ID') }}
              </th>
              <td>
                {{ $productCategory->id }}
              </td>
            </tr>
            <tr>
              <th>
                {{ __('Name') }}
              </th>
              <td>
                {{ $productCategory->name }}
              </td>
            </tr>
            <tr>
              <th>
                {{ __('Slug') }}
              </th>
              <td>
                {{ $productCategory->slug }}
              </td>
            </tr>
          </tbody>
        </table>
        <div class="form-group">
          <a class="btn btn-default"
            href="{{ route('admin.product-categories.index') }}">
            {{ __('Back to list') }}
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
