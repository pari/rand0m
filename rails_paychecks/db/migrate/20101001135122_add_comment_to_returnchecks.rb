class AddCommentToReturnchecks < ActiveRecord::Migration
  def self.up
    add_column :returnchecks, :comment, :text
  end

  def self.down
    remove_column :returnchecks, :comment
  end
end
